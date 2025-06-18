<?php
// Infrastructure/Repositories/EloquentUserRepository.php
namespace Infrastructure\Persistence\Repositories;

use Domain\User\Entity\User as DomainUser;
use Domain\User\Interfaces\UserRepositoryInterface;
use App\Models\User as EloquentUser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(string $id): ?DomainUser
    {
        $user = EloquentUser::find($id);
        return $user ? $this->toDomain($user) : null;
    }

    public function findByEmail(string $email): ?DomainUser
    {
        $user = EloquentUser::where('email', $email)->first();
        return $user ? $this->toDomain($user) : null;
    }

    public function findByPhone(string $phone): ?DomainUser
    {
        $user = EloquentUser::where('phone', $phone)->first();
        return $user ? $this->toDomain($user) : null;
    }
    public function save(DomainUser $user): void
    {
        $model = EloquentUser::find($user->getId()) ?? new EloquentUser(['id' => $user->getId()]);
        $model->name = $user->getName();
        $model->email = $user->getEmail();
        $model->password = $user->getPasswordHash();
        $model->avatar = $user->getAvatar();
        $model->save();
    }

    public function register(DomainUser $user): void
    {
        $model = new EloquentUser();
        $model->id = $user->getId() ?: Str::uuid()->toString(); // Generate a new UUID if not set
        $model->name = $user->getName();
        $model->email = $user->getEmail();
        $model->phone = $user->getPhone();
        $model->password = $user->getPasswordHash();
        $model->avatar = 'default-avatar.png'; // Set a default avatar or handle it as needed
        $model->save();
    }

    public function login(string $phone, string $password): ?DomainUser
    {
        $user = EloquentUser::where('phone', $phone)->first();
        if ($user && password_verify($password, $user->password)) {
            Auth::login($user, true); // Lưu session, true để "remember me"
            return $this->toDomain($user);
        }
        return null;
    }
    public function logout(string $userId): void
    {
        $user = EloquentUser::find($userId);
        if ($user && method_exists($user, 'currentAccessToken')) {
            $user->currentAccessToken()?->delete();
        }
    }
    public function updateProfile(DomainUser $user): void
    {
        $model = EloquentUser::find($user->getId());
        if ($model) {
            $model->name = $user->getName();
            $model->email = $user->getEmail();
            $model->avatar = $user->getAvatar();
            $model->save();
        }
    }
    protected function toDomain(EloquentUser $user): DomainUser
    {
        return new DomainUser(
            $user->id,
            $user->name,
            $user->email,
            $user->phone,
            $user->password,
            $user->avatar
        );
    }

    public function search(string $query): array
    {
        $authUser = Auth::user();

        if (!$authUser instanceof EloquentUser) {
            return [];
        }

        // Kiểm tra nếu là số điện thoại (chỉ chứa số, có thể +84...)
        if (preg_match('/^\+?[0-9]{6,15}$/', $query)) {
            // Tìm theo phone toàn bộ users, loại bỏ chính mình
            $users = EloquentUser::where('phone', 'like', "%{$query}%")
                ->where('id', '!=', $authUser->id)
                ->get();
        } else {
            // Tìm trong bạn bè theo tên
            $friends = $authUser->friends()
                ->where('name', 'like', "%{$query}%")
                ->get();

            $users = $friends;
        }

        // Convert sang mảng các DomainUser
        return $users->map(function ($user) {
            return new DomainUser(
                $user->id,
                $user->name,
                $user->email,
                $user->phone,
                $user->password,
                $user->avatar
            );
        })->all();
    }

    public function getFriends(string $userId): array
    {
        $user = EloquentUser::find($userId);
        if (!$user) {
            return [];
        }

        // Giả sử có quan hệ friends trong model User
        return $user->friends()->get()->map(function ($friend) {
            return new DomainUser(
                $friend->id,
                $friend->name,
                $friend->email,
                $friend->phone,
                $friend->password,
                $friend->avatar
            );
        })->all();
    }
}

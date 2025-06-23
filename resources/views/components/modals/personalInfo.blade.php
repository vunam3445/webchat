<!-- Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Thông tin cá nhân</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body text-center position-relative">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Avatar editable -->
                    <div class="avatar-container mx-auto position-relative">
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar"
                            class="rounded-circle avatar-img" width="100" height="100">

                        <!-- Camera icon -->
                        <span class="edit-avatar-icon">
                            <i class="bi bi-camera-fill"></i>
                            <input type="file" name="avatar" class="avatar-input" accept="image/*">
                        </span>
                    </div>

                    <!-- Name input -->
                    <div class="mb-3 mt-3">
                        <input type="text" name="name" value="{{ Auth::user()->name }}"
                            class="form-control text-center" placeholder="Your Name">
                    </div>

                    <!-- Email input -->
                    <div class="mb-3">
                        <input type="email" name="email" value="{{ Auth::user()->email }}"
                            class="form-control text-center" placeholder="Your Email">
                    </div>

            </div>


            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </div>
            </form> 
        </div>
    </div>
</div>

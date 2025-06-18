<?php
namespace Domain\Chat\Entity;

class Conversation
{
    public function __construct(
        public string $id,
        public string $type, // 'private' or 'group'
        public ?string $name = null,
        public array $participants = []
    ) {}
}

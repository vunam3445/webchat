<?php
namespace Domain\Chat\Entity;

class Conversation
{
    public function __construct(
        public string $id,
        public string $type, // 'private' or 'group'
        public array $participants = []
    ) {}
}

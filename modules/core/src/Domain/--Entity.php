// src/SharedKernel/Base/Entity.php
<?php
namespace SharedKernel\Base;

use SharedKernel\Contracts\IsEntity;

abstract class Entity implements IsEntity
{
    protected $id;
    protected $createdAt;
    protected $updatedAt;
    
    public function __construct(?string $id = null)
    {
        $this->id = $id ?? (string) \Ramsey\Uuid\Uuid::uuid4();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }
    
    public function getId(): string
    {
        return $this->id;
    }
    
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
    
    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
    
    protected function updateTimestamps(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
    
    public function equals(Entity $other): bool
    {
        return $this->id === $other->id;
    }
}
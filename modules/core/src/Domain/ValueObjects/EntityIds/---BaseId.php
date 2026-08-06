<?php

namespace SharedKernel\Ids;

use JsonSerializable;
use Stringable;

/**
 * Interface for all ID value objects.
 * Defines the contract that all ID classes must follow.
 */
interface BaseIdInterface extends Stringable, JsonSerializable
{
    /**
     * Get the underlying value of the ID
     * 
     * @return string|null The UUID value as string
     */
    public function getValue(): ?string;
    
    /**
     * Check if the ID is empty/new (not yet persisted)
     * 
     * @return bool
     */
    public function isEmpty(): bool;
    
    /**
     * Create a new instance from a string
     * 
     * @param string $value
     * @return static
     */
    public static function fromString(string $value): static;
    
    /**
     * Generate a new random ID
     * 
     * @return static
     */
    public static function generate(): static;
}



/**
 * Base class for all ID value objects.
 * Provides common ID behavior without business logic.
 */
abstract class BaseId implements BaseIdInterface
{
    
   protected ?string $value;
    
    /**
     * Create a new random ID
     */
    protected function __construct()
    {
        $this->value = $this->generateUuid();
    }
    
    /**
     * Create an ID from a specific UUID value
     * 
     * @param string|null $value The UUID value (null for empty ID)
     */
    protected function __constructFromValue(?string $value = null)
    {
        if ($value === null) {
            $this->value = null;
        } else {
            $this->validateUuid($value);
            $this->value = $value;
        }
    }
    
    /**
     * Create an ID from a string
     * 
     * @param string $value
     * @return static
     */
    public static function fromString(string $value): static
    {
        $id = new static();
        $id->value = $value;
        $id->validateUuid($value);
        return $id;
    }
    
    /**
     * Generate a new random ID
     * 
     * @return static
     */
    public static function generate(): static
    {
        return new static();
    }
    
    /**
     * Create an empty ID (for new entities before persistence)
     * 
     * @return static
     */
    public static function empty(): static
    {
        $id = new static();
        $id->value = null;
        return $id;
    }
    
    /**
     * Get the underlying value of the ID
     * 
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }
    
    /**
     * Check if the ID is empty/new
     * 
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->value === null;
    }
    
    /**
     * Generate a UUID v4
     * 
     * @return string
     */
    protected function generateUuid(): string
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            random_int(0, 0xffff), random_int(0, 0xffff),
            random_int(0, 0xffff),
            random_int(0, 0x0fff) | 0x4000,
            random_int(0, 0x3fff) | 0x8000,
            random_int(0, 0xffff), random_int(0, 0xffff), random_int(0, 0xffff)
        );
    }
    
    /**
     * Validate UUID format
     * 
     * @param string $uuid
     * @throws \InvalidArgumentException
     */
    protected function validateUuid(string $uuid): void
    {
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $uuid)) {
            throw new \InvalidArgumentException("Invalid UUID format: {$uuid}");
        }
    }
    
    /**
     * Compare two IDs for equality
     * 
     * @param BaseIdInterface $other
     * @return bool
     */
    public function equals(BaseIdInterface $other): bool
    {
        if ($this->isEmpty() && $other->isEmpty()) {
            return true;
        }
        
        if ($this->isEmpty() || $other->isEmpty()) {
            return false;
        }
        
        return $this->value === $other->getValue() && get_class($this) === get_class($other);
    }
    
    /**
     * Convert to string
     * 
     * @return string
     */
    public function __toString(): string
    {
        return $this->value ?? '';
    }
    
    /**
     * String representation
     * 
     * @return string
     */
    public function toString(): string
    {
        return $this->__toString();
    }
    
    /**
     * Serialize to JSON
     * 
     * @return string|null
     */
    public function jsonSerialize(): ?string
    {
        return $this->value;
    }
    
    /**
     * Get hash code for the ID
     * 
     * @return int
     */
    public function hashCode(): int
    {
        return crc32($this->value ?? '');
    }
}



/**
 * Example concrete implementation for a specific entity
 */
class UserId extends BaseId
{
    // You can add business-specific methods here
    public static function fromUserEmail(string $email): self
    {
        // Example: Create ID from email hash
        return self::fromString(uuid_create(UUID_TYPE_SHA1, uuid_ns_DNS(), $email));
    }
}






/**
 * Example concrete implementation for an Order entity
 */
class OrderId extends BaseId
{
    // Business-specific ID logic can be added here
    public function getPrefixedValue(): string
    {
        return 'ORD-' . $this->value;
    }
}


// Usage examples:
/*
// Generate new random ID
$userId = UserId::generate();
echo $userId->getValue(); // e.g., "550e8400-e29b-41d4-a716-446655440000"
echo (string)$userId; // Same as above

// Create from string
$orderId = OrderId::fromString('123e4567-e89b-12d3-a456-426614174000');

// Create empty ID for new entity
$newUserId = UserId::empty();

// Compare IDs
$userId1 = UserId::generate();
$userId2 = UserId::fromString($userId1->getValue());
var_dump($userId1->equals($userId2)); // true

// Reference other module entities
class User
{
    private UserId $id;
    
    public function __construct(UserId $id)
    {
        $this->id = $id;
    }
    
    public function getId(): UserId
    {
        return $this->id;
    }
}
*/

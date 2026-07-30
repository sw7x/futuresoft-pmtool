package com.example.customer.domain;

import jakarta.persistence.Embeddable;
import java.util.Objects;
import java.util.UUID;

@Embeddable
public class CustomerId {
    private String value; // TODO_ this is UUID value
    private String dbId; // TODO_ this is db pk value

    
    protected CustomerId() {} // for JPA
    
    public CustomerId(String value) {
        if (value == null || value.isBlank()) {
            throw new IllegalArgumentException("CustomerId cannot be null or blank");
        }
        this.value = value;
    }
    
    public static CustomerId generate() {
        return new CustomerId(UUID.randomUUID().toString());
    }
    
    public static CustomerId fromString(String value) {
        return new CustomerId(value);
    }
    
    public String getValue() {
        return value;
    }
    
    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        CustomerId that = (CustomerId) o;
        return Objects.equals(value, that.value);
    }
    
    @Override
    public int hashCode() {
        return Objects.hash(value);
    }
    
    @Override
    public String toString() {
        return value;
    }
}













<?php




namespace App\Domain\Customer;

//use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;
use Stringable;
use Illuminate\Support\Str;
/**
 * CustomerId Value Object
 * 
 * This immutable value object represents a Customer's unique identifier.
 * It ensures type safety and encapsulates UUID generation logic.
 */
class CustomerId implements Stringable
{
    private string $value;
    
    /**
     * Private constructor - use named constructors instead
     */
    private function __construct(string $value)
    {
        $this->ensureIsValidUuid($value);
        $this->value = $value;
    }
    
    /**
     * Generate a new random CustomerId
     */
    public static function generate(): self
    {
        //return new self((string) \Illuminate\Support\Str::uuid());
        return new self((string) Str::uuid());
    }
    
    /**
     * Create CustomerId from existing string
     */
    public static function fromString(string $value): self
    {
        return new self($value);
    }
    
    /**
     * Get the raw string value
     */
    public function getValue(): string
    {
        return $this->value;
    }
    
    /**
     * Validate UUID format
     */
    private function ensureIsValidUuid(string $value): void
    {
        if (empty($value) || !Str::isUuid($value)) {
        //if (empty($value) || !\Illuminate\Support\Str::isUuid($value)) {
            throw new InvalidArgumentException(
                sprintf('CustomerId must be a valid UUID. Got: %s', $value)
            );
        }
    }
    
    /**
     * Check equality with another CustomerId
     */
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
    
    /**
     * String representation
     */
    public function __toString(): string
    {
        return $this->value;
    }
}



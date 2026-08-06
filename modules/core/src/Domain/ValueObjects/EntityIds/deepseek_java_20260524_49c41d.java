// shared.kernel/src/main/java/com/sharedkernel/ids/BaseId.java
package com.sharedkernel.ids;

import java.io.Serializable;
import java.util.Objects;
import java.util.UUID;

/**
 * Base class for all ID value objects.
 * Provides common ID behavior without business logic.
 */
public abstract class BaseId implements Serializable {
    
    private final UUID value;
    
    protected BaseId() {
        this.value = UUID.randomUUID();
    }
    
    protected BaseId(UUID value) {
        this.value = value;
    }
    
    protected BaseId(String value) {
        this.value = UUID.fromString(value);
    }
    
    public UUID getValue() { return value; }
    
    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        BaseId baseId = (BaseId) o;
        return Objects.equals(value, baseId.value);
    }
    
    @Override
    public int hashCode() {
        return Objects.hash(value);
    }
    
    @Override
    public String toString() {
        return value.toString();
    }
}
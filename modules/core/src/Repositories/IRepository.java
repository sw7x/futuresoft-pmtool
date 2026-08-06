// shared.kernel/src/main/java/com/sharedkernel/interfaces/Repository.java
package com.sharedkernel.interfaces;

import java.util.List;
import java.util.Optional;

/**
 * Base repository interface for all modules.
 * Defines standard CRUD operations.
 */
public interface Repository<T extends Entity<ID>, ID extends Serializable> {
    
    T save(T entity);
    
    Optional<T> findById(ID id);
    
    List<T> findAll();
    
    void delete(T entity);
    
    void deleteById(ID id);
    
    boolean existsById(ID id);
    
    long count();
}
// employee.module/src/main/java/com/employeemodule/domain/Email.java
package com.employeemodule.domain;

import com.sharedkernel.base.ValueObject;  // ✅ Import from shared kernel

// Email extends ValueObject (gets equality contract)
public class Email extends ValueObject {
    
    private final String value;
    
    private Email(String value) {
        validateNotBlank(value, "Email");  // Helper from ValueObject
        validateEmailFormat(value);
        this.value = value.toLowerCase();
    }
    
    public static Email of(String value) {
        return new Email(value);
    }
    
    private void validateEmailFormat(String email) {
        if (!email.contains("@") || !email.contains(".")) {
            throw new DomainException("Invalid email format: " + email);
        }
    }
    
    public String getValue() { return value; }
    
    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        Email email = (Email) o;
        return value.equals(email.value);
    }
    
    @Override
    public int hashCode() {
        return value.hashCode();
    }
    
    @Override
    public String toString() {
        return value;
    }
}
# Phone Numbers SPA – Architecture Overview

## Introduction
This Laravel-based SPA validates and filters phone numbers for countries.

The application follows **clean architecture** and applies **SOLID principles** to achieve:
- Maintainability – easy to evolve
- Extensibility – new rules without rewrites
---

## Architecture Layers

### 1. Presentation Layer
**What:** User interface and HTTP handling  
**Components:** CustomerController, SPA frontend, Form validation  
**Job:** Handle requests/responses and user interactions

### 2. Application Layer
**What:** Business operation coordination  
**Components:** PhoneNumberService
**Job:** Orchestrate filtering, pagination, and data transformation

### 3. Domain Layer
**What:** Core business rules and validation  
**Components:** PhoneNumberParser, PhoneNumber, Country rules  
**Job:** Phone validation logic independent of framework

### 4. Infrastructure Layer
**What:** Data persistence and framework integration  
**Components:** Customer model, Service providers  
**Job:** Handle storage and Laravel-specific concerns

---

## Flow of Control
1. SPA sends request → Controller (Presentation Layer)
2. Controller validates → forwards to Service (Application Layer)
3. Service queries DB → applies Parser (Domain Layer)
4. Parser delegates to country rules → returns `PhoneNumber` objects
5. Response serialized → JSON → SPA

---

## SOLID Principles Applied

1. **Single Responsibility Principle (SRP)**
    - `PhoneNumberParser` parses only
    - `PhoneNumberService` orchestrates only
    - `PhoneNumber` is just a value object

2. **Open/Closed Principle (OCP)**
    - Add a new country by creating a new rule class (`KenyaRule`) without modifying existing parser/service

3. **Liskov Substitution Principle (LSP)**
    - All country rule classes implement `CountryRuleInterface`, so the parser can substitute one for another seamlessly

4. **Interface Segregation Principle (ISP)**
    - Country rules implement only what they need (validate/normalize)

5. **Dependency Inversion Principle (DIP)**
    - High-level services (`PhoneNumberService`) depend on abstractions (`CountryRuleInterface`, `PhoneRepositoryInterface`), not concrete classes

---

## Key Benefits
- Clear separation of concerns – modular and readable
- SOLID-compliant design – easier to extend and test
- Framework-agnostic domain – reusable across contexts
- Maintainable – localized changes with minimal ripple effects

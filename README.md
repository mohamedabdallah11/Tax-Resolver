# Definition of challenge

Different countries have different taxes applied when people purchase goods. Some countries have **Value Added Tax (VAT)**, other countries have **Goods and Services Tax (GST)**, while Canada has **Harmonized Sales Tax (HST)** and so on.

In some countries/states, we might have several taxes applicable to the purchase.

**So the goal is to have a microservice that will return all relevant taxes suitable for the provided location in a normalized fashion.**

# Project logic

We have two fictional tax providers: TaxBee and SeriousTax (which will be stored inside the `App/ExternalService` namespace). Everything inside `App/ExternalService` cannot be modified by the candidate. Candidate needs to handle inconsistencies in their code using best coding practices.

If the country is Canada or the United States - use the TaxBee service.

If the country is LT, LV, EE, PL, or DE - use the SeriousTax component.

On top of that, a caching layer must be implemented. 

# What we expect

- You will implement the project according to the SOLID principles.
- Logic will be divided into small and reusable components.
- You will write unit and integration tests
- End-to-end tests would be a bonus.
- Static code analysis tool integration into the project would be a bonus.

# Request structure

http://localhost:{port}/taxes?country=COUNTRY_CODE&state=FULL_STATE_NAME

Example - http://localhost:{port}/taxes?country=CA&state=Quebec

Note: state name should be case-insensitive.

# Request scenarios

This project must support these requests:

| Country   | State      | Taxes                     |
|-----------|------------|---------------------------|
| CA        | Quebec     | GST/HST: 5% , PST: 9.975% |
 | CA        | Ontario    | GST/HST: 13%              |
| US        | California | VAT: 7.25%                |
| LT        |            | VAT: 21%                  |
| LV        |            | VAT: 21%                  |
| EE        |            | VAT: 20%                  |
| PL        |            | Error: could not retrieve |
| DE        |            | VAT: 19%                  |


# Response structure

```json
[{"taxType": "VAT", "percentage": 19}, {"taxType": "GST", "percentage": 6}]
```

# Project setup

Run `make setup` to initialize local environment

How to run the tests: `make test-phpunit` (on template project)

Ssh to the container: `make bash` (on template project)


 

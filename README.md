## Loan API

### Development environment setup
Uses Laravel Sail to setup local development environment. 
- Make sure that you have composer installed on your system
- Run ```chmod +x ./setup.sh```
- Run ```./setup.sh```  

This should setup and start a server at http://localhost:8080. The MySQL server will be accessible on the port *33060*. These ports can be changed by changing the values of *APP_PORT* and *FORWARD_DB_PORT* values in .env file. All the emails send can be viewed using the MailHog dashboard at http://localhost:8025/  

### Running tests
```
./vendor/bin/sail test
```

### Admin user
The scaffolding creates and admin user with the credentials:  
```
email: admin@loan-api.com  
password: password
```

### Postman collection
https://www.getpostman.com/collections/daa3a4d85b0a64f08152

# Angular 2+/4/6 CRUD Code Generator
- Generate Angular component create/read/update/delete code from API
- Include a local Laravel back-end RESTful API demo
- Support custom and remote API 
- Easily add to exists Angular project
- Not effect to other exists components

# How to install
1. Requirements
    > php7+, npm, composer, sqlite db (already exists for most Linux/Mac/Windows7+)
2. Clone code
    ```
    git clone https://github.com/AlexStack/Angular2-CRUD-Generator-with-Laravel-RESTful-API-Demo.git angular-crud-generator
    ```
3. cd angular-crud-generator
    > cd angular-crud-generator
4. Run code generator script:
    - **For Windows**: run windows_ng_crud_generator.bat
        > windows_ng_crud_generator.bat
    - **For Mac or Linux**: run sh ./mac_linux_ng_crud_generator.sh
        > sh ./mac_linux_ng_crud_generator.sh
5. Done

# How it works
- The generator script will run npm install for Angualr environment and run composer update for Laravel back-end RESTful API environment
- It's will need 3-10 minutes for the first time
    > For windows os, it will open another 2 cmd windows for Angular frontend ng serve and Laravel API back-end server
    > For Mac and linux os, it will run Angular frontend ng serve and Laravel API back-end server in the same terminal
- Run the same script again if you need generate more Angular code
- There are few API demos exists, you can use your own RESTful API from local or remote server

# Some screenshots


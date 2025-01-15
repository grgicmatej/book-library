## Introduction

Cogify lightweight RESTful API to manage a collection of books in
a library.

## Content
### 1. [Getting Started](#getting-started)
### 2. [Managing the Project](#managing-the-project)

## Getting Started

1.1 Clone the Repository:

```
git clonegit@github.com:grgicmatej/book-lib.git
cd book-lib
```
1.2 Build and Install Dependencies:
```
make build
```

1.3.1. Copy `.env.dist` to  `.env`  
1.3.2. Copy `.env.local.dist` to `.env.local`  
1.3.3. Edit database values in both files

1.4 Start Docker Services:
```
make start
```

1.5 Access the PHP Container:
```
make ssh
```

Optional: make GET request to the healthCheck API to ensure everything is working:
```
GET http://__base_url__/api/v1/healthCheck
```

## Managing the Project

2.1 Stop the Docker Services:
```
make stop
```

### Running Tests

3.1 Run all tests inside php container:
```
composer test-check
```

3.2 Run quality control tests inside php container:
```
composer code-check
```

3.3. Run complete code check via makefile:
```
make complete-check
```

### Customization

4.1 Customizing Docker Compose:

Modify the docker/docker-compose.override.yaml file for additional configurations.

### Notes

5.1 Container Name:

The Docker container name is set to cogify-php-1 by default, and it can be changed in the Makefile (CONTAINERNAME variable).

Additional Information

For more information about the project and its components, refer to the project's source code and documentation.

### Bundle the specification

Open API specification is chunked into several different .yaml files. This is to avoid having a single large file with routes, request bodies and responses.
To bundle the documentation and create single yaml file with routes, requests and responses follow the next steps:
- make sure you have latest version of Node installed
- install swagger-cli globally by running `npm install -g @apidevtools/swagger-cli`
- Position into `docs > OpenApi > open_api.yaml`
- run `swagger-cli bundle -t yaml -r open_api.yaml --outfile open_api_bundled.yaml`

### API endpoint collection

Please find postman collection in the root of this project. Said collection contains all the endpoints and their respective payloads.
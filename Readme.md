## Local Env

Create .env

```bash
CONTAINER_NAME=omnapp

RDS_HOST=your_host
DATABASE_NAME=your_database_name
DATABASE_USER=your_user
DATABASE_ROOT_PASSWORD=your_passwor
```

Build the Docker Image
```bash
docker build -t openmynetwork-wp:latest .
```

Login to ECR from the CLI
```bash
aws ecr get-login-password --region <your-region> | docker login --username AWS --password-stdin <your-aws-account-id>.dkr.ecr.<your-region>.amazonaws.com 
```
Example
```bash
aws ecr get-login-password --region us-east-1 | docker login --username AWS --password-stdin 22*****.dkr.ecr.us-east-1.amazonaws.com
```

Tag your Docker image for ECR
```bash
docker tag openmynetwork-wp:latest <your-aws-account-id>.dkr.ecr.<your-region>.amazonaws.com/openmynetwork-ecr:latest
```

Push the Docker image to ECR
```bash
docker push <your-aws-account-id>.dkr.ecr.<your-region>.amazonaws.com/openmynetwork-ecr:latest
```
# Open My Network

## AWS Ec2
Here I am using ec2 instance to host my code. Launch a instance, you can choose t2.medium and 30gb volume

## Setup Runner
1. Create a github repo
2. Go to setting > Actions > Runners
3. Add New Self hosted runner and follow the installation guide for ubuntu
4. After all, run
    a. sudo ./svc.sh install
    b. sudo ./svc.sh start

## Setup Docker

```bash
  sudo apt update
```

```bash
  sudo apt install docker.io -y
```

```bash
  sudo systemctl start docker
  sudo systemctl enable docker
```

```bash
  sudo docker login -u user_name
```

```bash
  docker info
```

```bash
  sudo usermod -aG docker ubuntu
```

```bash
  newgrp docker
```

Build Docker Image and Push to repo
```bash
  docker build -t openmynetwork/wordpress-app:latest .
```

```bash
  docker pull openmynetwork/wordpress-app:latest
```

```bash
  docker push openmynetwork/wordpress-app:latest
```

```bash
  docker tag openmynetwork/wordpress-app:latest openmynetwork/wordpress:latest
```
## Setup Nginx

```bash
  sudo apt install nginx -y
```

```bash
  sudo systemctl start nginx
  sudo systemctl enable nginx
```

## Generate SSL

```bash
  sudo apt install certbot python3-certbot-nginx
```

```bash
  sudo certbot --nginx -d openmynetwork.com -d www.openmynetwork.com
```
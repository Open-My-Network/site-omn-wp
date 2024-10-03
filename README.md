# Open My Network

## Using Locally

```bash
  cd /project_dir/site-omn-wp
```

Build you image
```bash
  docker-compose up -d
```

Once image is built, run locally
```bash
  http://localhost:3000
```

After completed work push your image and Repo
```bash
  docker build -t openmynetwork/wordpress-app:latest .
  docker push openmynetwork/wordpress-app:latest
```

```bash
git add .
git commit -m "your message"
git push origin branch_name
```

```bash
docker push openmynetwork/wordpress-app:latest
```

After Changes Pull Image and Repo
```bash
git pull origin main
docker pull openmynetwork/wordpress-app:latest
docker-compose up -d
```

Stop and Remove
```bash
docker stop wordpress
docker rm wordpress
```

Pushing Docker Image
Follow this step only if not login
```bash
docker login -u your_username
```

If loggedin then,
```bash
docker push openmynetwork/wordpress-app:latest
```

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
  sudo usermod -aG docker "$USER"
```

## Setup Nginx

```bash
  sudo apt install nginx -y
```

```bash
  sudo systemctl start nginx
  sudo systemctl enable nginx
```

### Configuration for Nginx

```bash
  sudo vi /etc/nginx/sites-available/openmynetwork.com
```

```bash
server {
    listen 80;
    server_name openmynetwork.com www.openmynetwork.com;

    location / {
        proxy_pass http://localhost:3000; # Adjust to your application's port
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }

    error_page 404 /404.html;
    location = /404.html {
        internal;
    }
}
```

```bash
  sudo ln -s /etc/nginx/sites-available/openmynetwork.com /etc/nginx/sites-enabled/
```

```bash
  sudo ufw enable
```

```bash
  sudo ufw allow 'Nginx Full'
```

```bash
  sudo ufw allow 3000
```

## Generate SSL

```bash
  sudo apt install certbot python3-certbot-nginx
```

```bash
  sudo certbot --nginx -d openmynetwork.com -d www.openmynetwork.com
```
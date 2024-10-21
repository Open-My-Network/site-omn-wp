
# Open My Network (LEEP)

- [Wordpress](https://wordpress.com/)
- [AWS](https://aws.amazon.com/)
    - EC2
    - RDS
- [Docker](https://docker.com/)

## Acknowledgements

 - [Awesome Readme Templates](https://awesomeopensource.com/project/elangosundar/awesome-README-templates)
 - [Awesome README](https://github.com/matiassingers/awesome-readme)
 - [How to write a Good readme](https://bulldogjob.com/news/449-how-to-write-a-good-readme-for-your-github-project)


## Teams

- [Abishek Khanal](https://www.github.com/abishek12)


## Features

- Light/dark mode toggle
- Live previews
- Fullscreen mode
- Cross platform

## Color Reference

| Color             | Hex                                                                |
| ----------------- | ------------------------------------------------------------------ |
| Example Color | #0a192f |
| Example Color | #f8f8f8 |
| Example Color | #00b48a |
| Example Color | #00d1a0 |


## Environment Variables

To run this project, you will need to add the following environment variables to your .env file

`MYSQL_ROOT_PASSWORD`=""

`MYSQL_DATABASE`="database_name"

`MYSQL_USER`="username"

`MYSQL_PASSWORD`="password"


## Build and Push Manually

Build the project
```bash
  docker build -t openmynetwork/wordpress-app .
```

Tag the Image for Docker Hub
```bash
  docker tag openmynetwork/wordpress-app openmynetwork/wordpress-app:latest
```

Finally, push the image to Docker Hub:
```bash
  docker push openmynetwork/wordpress-app:latest
```

To Sync Project with Server
```bash
rsync -avz -e "ssh -i ~/Downloads/openmynetwork.pem" <username>:<server_path> <destination_path>
```
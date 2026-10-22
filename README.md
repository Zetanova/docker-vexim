## docker build 
```
docker build --build-arg VEXIM_VERSION=2.3 --build-arg VEXIM_GID=1000 --build-arg VEXIM_UID=1000 --build-arg VEXIM_MAILROOT="/var/vmail01/" -t zetanova/vexim:2.4-rc1 -t zetanova/vexim:latest .
docker push zetanova/vexim:2.4-rc1
docker push zetanova/vexim:latest
```

## docker setup
```
docker run -d --name vexim --restart=unless-stopped \
    -p 8181:80 \
    -e VEXIM_SQL_SERVER="mysqlserver" \
    -e VEXIM_SQL_USER="vexim" \
    -e VEXIM_SQL_PASS="mypassword" \
    zetanova/vexim:2.4-rc1
   
```
## docker build 
```
docker build --build-arg VEXIM_VERSION=2.3 --build-arg VEXIM_GID=90 --build-arg VEXIM_UID=90 -t zetanova/vexim:2.3 -t zetanova/vexim:latest .
docker push zetanova/vexim:2.3 
docker push zetanova/vexim:latest
```

## docker setup
```
docker run -d --name vexim --restart=unless-stopped \
    -p 8181:80 \
    -e VEXIM_SQL_SERVER="mysqlserver" \
    -e VEXIM_SQL_USER="vexim" \
    -e VEXIM_SQL_PASS="mypassword" \
    zetanova/vexim:2.3
   
```
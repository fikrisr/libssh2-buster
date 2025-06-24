docker build -t ssh2-test -f Dockerfile.test .

docker run --rm -it ssh2-test bash

// port forwading 127.0.0.1 ke 0.0.0.0 biar loopback nya bisa semua interface
netsh interface portproxy add v4tov4 listenaddress=0.0.0.0 listenport=2222 connectaddress=127.0.0.1 connectport=2222

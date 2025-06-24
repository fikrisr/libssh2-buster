# PHP 7.3 + ssh2.so + libssh2 (Custom Build)

This repository contains a portable and isolated Docker environment for testing the [`ssh2`](https://www.php.net/manual/en/book.ssh2.php) PHP extension with a **custom-built libssh2** (version ≥ 1.10) to support modern SSH servers (OpenSSH 8/9+).

> Target: Run and test `ssh2_connect()` with public key authentication on Debian Buster compatible systems, without system-wide installation.

---

## What’s Included

- PHP 7.3 CLI (from `php:7.3-cli`)
- `libssh2.so.1.0.1` (custom built from source)
- `ssh2.so` (compiled against the custom `libssh2`)
- Minimal Docker image for testing

---

## How to Use

### 1. Clone this repo

```
git clone https://github.com/fikrisr/libssh2-buster.git
cd libssh2-buster
```

### 2. Build Docker image

```
docker build -t ssh2-test -f Dockerfile.test .
```

### 4. Run the test

```
docker run --rm -it ssh2-test bash
php sftp.php
// SUCCESS: Connected and Authenticated
```

## Building libssh2 and ssh2.so

If you want to rebuild libssh2 or ssh2.so, you can create a separate builder Dockerfile (see Dockerfile.libssh)

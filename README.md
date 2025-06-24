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

## Deployment PHP ssh2 Extension with Custom libssh2 on Server

### Requirements

- Debian/Ubuntu server with PHP 7.3 and Apache2 installed
- glibc >= 2.28
- libssl >= 1.1.1
- Built files:
  - `ssh2.so` (PHP extension)
  - `libssh2.so.1` (shared library)

## Deployment Steps

### 1. Clone this repo

```
git clone https://github.com/fikrisr/libssh2-buster.git
cd libssh2-buster
```

### 2. Copy files

```
# Copy the PHP extension
sudo cp ssh2.so /usr/lib/php/20180731/

# Copy the shared library
sudo cp lib/libssh2.so.1.0.1 /usr/local/lib/

# Tip: confirm your extension dir with:
php -i | grep extension_dir
```

### 3. Create Symbolic Links

```
cd /usr/local/lib
sudo ln -sf libssh2.so.1.0.1 libssh2.so.1
sudo ln -sf libssh2.so.1 libssh2.so
```

### 4. Configure Dynamic Linker

If /usr/local/lib is not already included in your dynamic linker config, create a new file:

```
echo "/usr/local/lib" | sudo tee /etc/ld.so.conf.d/libssh2-custom.conf
```

Then update the linker cache:

```
sudo ldconfig
```

verify it:

```
ldconfig -p | grep libssh2

# output
# libssh2.so.1 (libc6,x86-64) => /usr/local/lib/libssh2.so.1
```

### 5. Enable ssh2 Extension

Create the configuration file 20-ssh2.ini in both CLI and Apache configuration folders:

```
echo "extension=ssh2.so" | sudo tee /etc/php/7.3/cli/conf.d/20-ssh2.ini
echo "extension=ssh2.so" | sudo tee /etc/php/7.3/apache2/conf.d/20-ssh2.ini
```

verify it:

```
php -i | grep ssh2
```

### 6. Set Apache LD_LIBRARY_PATH

Edit Apache's environment config:

```
sudo nano /etc/apache2/envvars
```

Add this line to the end:

```
export LD_LIBRARY_PATH=/usr/local/lib
```

Restart Apache2

```
sudo systemctl restart apache2
```

Now you can test the ssh2 extension

# Troubleshooting

- Check Apache logs:
  `sudo tail -f /var/log/apache2/error.log`
- Make sure libssh2.so.1 is being loaded from /usr/local/lib:
  ```
  lsof -p $(pgrep apache2 | head -n1) | grep libssh2
  ```
- Ensure ldconfig output points to the correct library.

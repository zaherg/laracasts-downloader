# Laracasts Downloader

This is a CLI application which will download **Laracasts** courses, if and **only** if you are a member.

__PS__: This is still a prototype that I've wrote in few hours, so it still needs a lot of works.

## Usage

1. Clone the repository
```
git clone https://github.com/linuxjuggler/laracasts-downloader.git
```

2. Install the dependencies using `composer`

```
composer install
```

3. Make sure you have a `.env` file similar to `.env.example` but add your login information

4. Run the command:

```
./laracasts download <slug1> <slug2> <slug3>
```

replace `<slug1>` with the course you want to download, for example, the following command will download the 
following courses

1. Laravel Nova Mastery 
1. What's New in Laravel 5.7
1. Webpack For Everyone

```
./laracasts download whats-new-in-laravel-5-7 webpack-for-everyone laravel-nova-mastery
```

## Building Docker image

1. Clone the repository

```
git clone https://github.com/linuxjuggler/laracasts-downloader.git
```

2. Make sure you have [Docker](https://docker.com) installed

3. Run the following command from within the code directory to build the image

```
./build
```

4. Make sure you have a `.env` file similar to `.env.example` but add your login information

5. Run the following command to work with the image

```
docker run --rm -it \
    -v .env=/src/app/.env \
    -v ~/Download=/src/app/download \
    zaherg/laracasts-downloader:latest \
    download <slug1> <slug2> <slug3>
```

**PS**: Make sure the `DOWNLOAD_DIR` value is `/src/app/download` in this cause.




# NOTE
 
1. THE SOFTWARE IS PROVIDED "AS IS", so am not responsible if Jeffery got mad of you.
2. The application may stop working at any giving moment, when the site structure change, and I can't promise I will fix it. 

## Inspiration 

This work was inspired from [Hakan ERSU](https://github.com/hakanersu) and his work on 
[codecourse-downloader](https://github.com/hakanersu/codecourse-downloader).
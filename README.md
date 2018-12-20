# Laracasts Downloader

This is a CLI application which will download **Laracasts** courses, if and **only** if you are a member.

__PS__: This is still a prototype that I've wrote in few hours, so it still needs a lot of works.

## Usage

1. Clone the repository
```
git clone 
```

2. Install the dependencies using `composer`

```
composer install
```

3. Run the command:

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

# NOTE
 
THE SOFTWARE IS PROVIDED "AS IS", so am not responsible if Jeffery got mad of you.

## Inspiration 

This work was inspired from [Hakan ERSU](https://github.com/hakanersu) and his work on 
[codecourse-downloader](https://github.com/hakanersu/codecourse-downloader).
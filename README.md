# social_signin

Elgg plugin for register/sign in using Facebook, Google and Linkedin.

![Social SignIn screenshot](screenshot.jpg)

## Prerequistes

- Elgg 2.3.X
- PHP >= 5
- composer

## Install on Elgg

1. Run composer inside on social_signin directory, locally or in server where Elgg is placed.

```
composer install
```

2. Copy root directory to the *mod* Elgg's directory

3. As asmin user, enable Plugin 'Social SignIn' in Administration page.

4. As admin user, go to Administration -> Settings -> Social SignIn and set OAuth Credentials for Facebook, Google and Linkedin

### Redirect URL for Linkedin.

**For Linekdin OAuth config , redirect URL must be <url_elgg>/action/account/social_signin**

## Development

### Edit styles

Plugin includes a SASS files for development styles. For generate CSS, you need Nodejs and Gulp.

Run

```
npm install
```

Edit SASS files inside scss directory and generate css with:

```
gulp sass
```

You can use

```
gulp watch-sass
```

for hot editing styles.

# License

SocialSignIn Elgg plugin is licensed under the MIT license. (http://opensource.org/licenses/MIT)

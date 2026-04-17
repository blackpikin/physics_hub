# physics_hub
A web app for Physics MCQ and other examination questions

## Production configuration

Set these environment variables in production:

- `APP_ENV=production`
- `DB_HOST=<your_db_host>`
- `DB_NAME=<your_db_name>`
- `DB_USER=<non_root_db_user>`
- `DB_PASS=<strong_db_password>`

When `APP_ENV=production`, the app will refuse to connect if:

- `DB_USER` is `root`
- `DB_PASS` is empty

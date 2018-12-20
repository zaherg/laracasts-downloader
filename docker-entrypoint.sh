#!/bin/sh
set -e

isCommand() {
  for cmd in \
    "help" \
    "list" \
    "download"
  do
    if [ -z "${cmd#"$1"}" ]; then
      return 0
    fi
  done

  return 1
}

# check if the first argument passed in looks like a flag
if [ "$(printf %c "$1")" = '-' ]; then
  set -- /sbin/tini -- php /src/app/laracasts "$@"
# check if the first argument passed in is composer
elif [ "$1" = 'php' ]; then
  set -- /sbin/tini -- "$@"
# check if the first argument passed in matches a known command
elif isCommand "$1"; then
  set -- /sbin/tini -- php /src/app/laracasts "$@"
fi

exec "$@"
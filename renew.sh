#! /bin/bash
set -e

cd "$(dirname "$0")"

# Renew the certificate if it's within 30 days of expiry, then reload nginx to pick it up
docker compose -f docker-compose.prod.yml run --rm certbot renew --cert-name kirstensiebach.com --quiet
docker compose -f docker-compose.prod.yml exec -T webserver nginx -s reload

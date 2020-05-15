#!/bin/bash

rsync -vaz ~/public_html/DEV-SITE/cache/charts ~/public_html/PUBLIC-SITE/cache

rsync -vaz ~/public_html/DEV-SITE/cache/secured/external_api ~/public_html/PUBLIC-SITE/cache/secured

#!/bin/sh

project_env="gcp-qatest"
service_site="cronjob"
PHP_RUN_CMD="php /var/www/html/artisan"

function execCronJob(){
    local method="${1}"
    echo "$(date +"%F %T") [execCronJob] PROJECT_ENV="${project_env}" SERVICE_SITE="${service_site}" ${PHP_RUN_CMD} ${method}"
    PROJECT_ENV="${project_env}" SERVICE_SITE="${service_site}" ${PHP_RUN_CMD} ${method}
}

execCronJob "store-bet-type-count" &
execCronJob "monitor-error-count" &
execCronJob "cache-event-list" &
execCronJob "cache-live-list" &
sleep 60

# PROJECT_ENV=gcp-qatest SERVICE_SITE=cronjob php /var/www/html/artisan store-bet-type-count
# PROJECT_ENV=gcp-qatest SERVICE_SITE=cronjob php /var/www/html/artisan monitor-error-count
# PROJECT_ENV=gcp-qatest SERVICE_SITE=cronjob php /var/www/html/artisan cache-event-list
# PROJECT_ENV=gcp-qatest SERVICE_SITE=cronjob php /var/www/html/artisan cache-live-list

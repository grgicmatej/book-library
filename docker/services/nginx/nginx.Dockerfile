FROM nginx:alpine

ARG USER_ID=1000
ARG GROUP_ID=1000

# Create app user and group with UID same as provided argument to avoid permission issues and conflicts
RUN echo "Creating app:app user and group with IDs $USER_ID:$GROUP_ID" \
    && addgroup -g $GROUP_ID -S app \
    && adduser -S app -D -u $USER_ID -G app

COPY docker/services/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/services/nginx/nginx.conf /etc/nginx/nginx.conf

#RUN chown -R app:app /opt/app
COPY ./public /opt/app/public

#USER app
USER root

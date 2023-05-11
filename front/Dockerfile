# pull official base image
FROM node:19.8.1-alpine3.17 as builder

# set working directory
WORKDIR /app

# add `/app/node_modules/.bin` to $PATH
ENV PATH /app/node_modules/.bin:$PATH

# install app dependencies
COPY package.json yarn.lock ./

# install app dependencies
RUN yarn install --ignore-scripts --non-interactive --frozen-lockfile --network-timeout 100000

# add project files
COPY .env .env.* index.html tsconfig.json tsconfig.node.json vite.config.ts ./
COPY public public/
COPY src src/

## build the project for production
FROM builder as prod-builder

RUN yarn build:prod

### PROD STAGE
FROM nginx:1.16-alpine as webapp

WORKDIR /app

COPY ./.docker/nginx/default.conf /etc/nginx/nginx.conf

## Remove default nginx index page
RUN rm -rf /usr/share/nginx/html/*

# Copy from the stahg 1
COPY --from=prod-builder /app/dist /app

EXPOSE 80

## build the project for production
FROM builder as devenv-builder

RUN yarn build:dev

### PROD STAGE
FROM nginx:1.16-alpine as devenv-webapp

WORKDIR /app

COPY ./.docker/nginx/default.conf /etc/nginx/nginx.conf

## Remove default nginx index page
RUN rm -rf /usr/share/nginx/html/*

# Copy from the stahg 1
COPY --from=devenv-builder /app/dist /app

EXPOSE 80

ENTRYPOINT ["nginx", "-g", "daemon off;"]

### DEV STAGE
FROM builder as dev

# add app
COPY .eslintrc.cjs .prettierrc.cjs jest.config.cjs ./
COPY tests tests/

## LABELS
LABEL maintainer="yosribahri@gmail.com"
LABEL org.opencontainers.image.source="https://github.com/devgine/company-versioning/"
LABEL org.opencontainers.image.description="Company versioning web app"
LABEL org.label-schema.schema-version="1.0"
LABEL org.label-schema.build-date=$BUILD_DATE
LABEL org.label-schema.name="devgine/company-versioning-webapp"
LABEL org.label-schema.description="Company versioning web app"
LABEL org.label-schema.url="https://devgine.github.io/company-versioning/"
LABEL org.label-schema.vcs-url="https://github.com/devgine/company-versioning/"
LABEL org.label-schema.vcs-ref=$VCS_REF
#LABEL org.label-schema.vendor="WSO2"
LABEL org.label-schema.version=$BUILD_VERSION
LABEL org.label-schema.docker.cmd="docker run -d ghcr.io/devgine/company-versioning-webapp:dev"

# start app
CMD ["yarn", "host"]

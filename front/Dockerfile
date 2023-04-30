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
COPY .env .env.* index.html tsconfig.json tsconfig.node.json vite.config.ts index.html ./
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

ENTRYPOINT ["nginx", "-g", "daemon off;"]

### DEV STAGE
FROM builder as dev

# add app
COPY .eslintrc.cjs .prettierrc.cjs ./

# start app
CMD ["yarn", "host"]

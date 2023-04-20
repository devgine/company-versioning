# pull official base image
FROM node:19.8.1-alpine3.17 as common

# set working directory
WORKDIR /app

# add `/app/node_modules/.bin` to $PATH
ENV PATH /app/node_modules/.bin:$PATH

# install app dependencies
COPY package.json ./
COPY yarn.lock ./

FROM common as prod

ENV NODE_ENV=production

# install app dependencies without dev dependencies
RUN yarn install --production=true

# add app
COPY .env index.html tsconfig.json tsconfig.node.json vite.config.ts ./
COPY public public/
COPY src src/

# start app
CMD ["yarn", "start"]

FROM common as dev

# install app dependencies
RUN yarn install

# add app
COPY .env index.html tsconfig.json tsconfig.node.json vite.config.ts ./
COPY public public/
COPY src src/

# start app
CMD ["yarn", "dev", "--host"]

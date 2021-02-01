FROM node:lts-alpine

WORKDIR /usr/src/app/source

COPY package*.json ./

RUN npm install

EXPOSE 8080

CMD ["npm", "run", "serve"]
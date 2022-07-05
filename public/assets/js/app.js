import Binding from './binding';

class App {

    constructor() {

        this.options = require( './options' );

        this.methods = Binding;

    }

    build() {

        return this;

    }

}

const app = new App();

app.build();

module.exports = app;
const React = require('react');
const ReactDOM = require('react-dom');
// const TrackVisibility = require('react-on-screen');

import TrackVisibility from 'react-on-screen';


class HelloMessage extends React.Component {
    render() {
        const style = {
            background: this.props.isVisible ? 'red' : 'blue'
        };
        return (
            <div style={style}>
                <h1>Hello, {this.props.name}!</h1>
                <h2>It is {new Date().toLocaleTimeString()}.</h2>
            </div>
        );
    }
}


function tick(greeterName) {
    ReactDOM.render(
        <TrackVisibility>
            {({isVisible}) => <HelloMessage name={greeterName} isVisible={isVisible}/>}
        </TrackVisibility>
        , document.getElementById('greeter'));
}

setInterval(tick, 1000, ['Peter']);

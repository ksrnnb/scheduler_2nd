import React from 'react';
import ReactDOM from 'react-dom';

class Test extends React.Component {
  render() {
    return (
      <p>Hello!</p>
    );
  }
}

ReactDOM.render(
  <Test />,
  document.getElementById('root')
);
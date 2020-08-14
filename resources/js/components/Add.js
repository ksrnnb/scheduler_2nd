import React from 'react';
import ReactDOM from 'react-dom';
import { divide } from 'lodash';

function TableData(props) {
  const symbols = ['○', '△', '×'];
  const handleClick = props.onClick;
  const rowIndex = props.rowIndex;
  const availability = props.availability;
  
  const tableData = symbols.map((symbol, symbolIndex) => {

    // availability is string...
    if (availability == symbolIndex) {

      return <td key={symbolIndex} onClick={() => handleClick(rowIndex, symbolIndex)} className="selected">{symbol}</td>;
    } else {
      return <td key={symbolIndex} onClick={() => handleClick(rowIndex, symbolIndex)}>{symbol}</td>;
    }
  });

  return tableData;
}

class TableRows extends React.Component {
  constructor(props) {
    super(props);
    this.candidates = this.props.candidates;
    this.handleClick = this.props.onClick.bind(this);
  }
  render() {

    const handleClick = this.handleClick;

    const rows = Array.prototype.map.call(this.candidates, function(candidate, index) {
      // need to use key
      return <tr key={candidate.name} scope="row"><td>{candidate.dataset.date}</td><TableData onClick={handleClick} availability={candidate.value} rowIndex={index} /></tr>;
    });

    return rows;
  }
}

class Candidates extends React.Component {

  constructor(props) {
    super(props);

    this.candidates = document.getElementsByClassName('candidates');
    
    // initial input value = 0
    Array.prototype.map.call(this.candidates, candidate => {
      return candidate.value = 0;
    });

    this.state = {
      candidates: document.getElementsByClassName('candidates')
    };

    // bind is necessary...
    this.handleClick = this.handleClick.bind(this);
  }

  handleClick(rowIndex, symbolIndex) {
    const candidates = this.state.candidates;

    // update class .selected and input value
    candidates[rowIndex].value = symbolIndex;

    this.setState({
      candidates: candidates,
    });
  }


  render() {
    // const or let ??
    const candidates = this.state.candidates;

    return (
      <div>
        {/* Need to adjust table width later */}
        <table className="table-bordered text-center" style={{width: "100%"}}>
          <thead>
            <tr>
              <th>Date</th><th scope="col"></th><th scope="col"></th><th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <TableRows onClick={this.handleClick} candidates={candidates}/>
          </tbody>
        </table>
      </div>
    );
  }
}


if (document.getElementById('add')) {
  ReactDOM.render(
  <Candidates />,
    document.getElementById('add')
  );
}
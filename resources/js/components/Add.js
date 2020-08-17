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

      return <td key={symbolIndex} onClick={() => handleClick(rowIndex, symbolIndex)} className="selected able">{symbol}</td>;
    } else {
      return <td key={symbolIndex} onClick={() => handleClick(rowIndex, symbolIndex)} className="able">{symbol}</td>;
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

function AddButton() {
  return <input type="submit" id="submit-button" className="btn btn-outline-primary" name="add" value="Add user" />
}

function DeleteButton(props) {
  const hidden = props.ishidden ? ' display-none' : '';
  return <input type="submit" id="delete-button" className={"btn btn-outline-danger" + hidden} name="delete" value="Delete user" />
}

class ResetButton extends React.Component {

  constructor(props) {
    super(props);
    this.resetClick = this.props.onClick.bind(this);
  }

  render() {
    return <button id="reset-button" className="btn btn-outline-success w-100 mb-5" onClick={this.resetClick}>Reset input information</button>
  }
}

class Candidates extends React.Component {

  constructor(props) {
    super(props);

    this.handleClick = this.props.handleClick.bind(this);
  }

  render() {
    // const or let ??
    const candidates = this.props.candidates;
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

class UserName extends React.Component {

  constructor(props) {
    super(props);
  }

  render() {
    const userId = this.props.userId;
    const userName = this.props.userName;

    return (
      <div>
        
        {/* <input type="hidden" id="user-id" name="userId" value={userId}/>
        <input type="text" id="user-name" name="userName" value={userName} required /> */}
      </div>
    );
  }
}

class UserAddForm extends React.Component {

  constructor(props) {
    super(props);

    const candidates = document.getElementsByClassName('candidates');
    
    // initial input value = 0
    Array.prototype.map.call(candidates, candidate => {
      return candidate.value = 0;
    });

    this.state = {
      candidates: candidates,
      ishidden: true,
      userId: '',
      userName: '',
    };

    // bind is necessary...
    this.handleClick = this.handleClick.bind(this);
    this.resetClick = this.resetClick.bind(this);
  }

  resetClick(e) {
    e.preventDefault();
    const candidates = this.state.candidates;

    Array.prototype.map.call(candidates, candidate => {
      return candidate.value = 0;
    });

    // this.resetUser();
    
    this.setState({
      candidates: candidates,
      ishidden: true,
      userId: '',
      userName: '',
    });
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
    const candidates = this.state.candidates;
    const userName = this.state.userName;
    const userId = this.state.userId;
    return (
      <div>
        <UserName userName={userName} userId={userId}/>
        <Candidates handleClick={this.handleClick} candidates={candidates}/>
        <AddButton />
        <DeleteButton ishidden={this.state.ishidden} />
        <ResetButton onClick={this.resetClick} candidates={candidates}/>
      </div>
    );
  }
}


if (document.getElementById('add')) {
  ReactDOM.render(
  <UserAddForm />,
    document.getElementById('add')
  );
}
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
        <p>Candidates</p>
        {/* Need to adjust table width later */}
        <table className="table-bordered text-center">
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

    this.onChange = this.props.onChange;
  }

  render() {
    const userId = this.props.userId;
    const userName = this.props.userName;

    return (
      <div>
        <p>User Name</p>
        <input type="hidden" id="user-id" name="userId" value={userId}/>
        <input type="text" id="user-name" name="userName" value={userName} onChange={this.onChange} required />
      </div>
    );
  }
}

class AvailabilitiesTable extends React.Component {

  constructor(props) {
    super(props);

    this.userNameTableHeader = this.userNameTableHeader.bind(this);
    this.availabilitiesTableData = this.availabilitiesTableData.bind(this);
  }

  userNameTableHeader() {
    const tableHeader = [];

    this.props.users.forEach(user => {
      tableHeader.push(<th key={user.userId} scope="col"><a className="users" data-id={user.userId} href="#input-title" method="GET">{user.userName}</a></th>);
    });

    return tableHeader;
  }

  getAvailability(availabilities, i) {

    const tableData = [];

    // availabilities: [{userId: availability, userId: availability, ...}]
    const availabilitiesObj = availabilities[i];
    const availabilitiesArray = Object.values(availabilitiesObj);


    availabilitiesArray.forEach((availability, j) => {

      let symbol;

      switch (availability) {
        case '0':
          symbol = '○';
          break;
        case '1':
          symbol = '△';
          break;
        case '2':
          symbol = '×';
          break;
      }

      tableData.push(<td key={i + '_' + j}>{symbol}</td>);
    });

    return tableData;
  }

  availabilitiesTableData() {
    const tableData = [];
    const candidates = this.props.candidates;
    const availabilities = this.props.availabilities;

    candidates.forEach((candidate, i) => {
      tableData.push(<tr key={candidate + '_' + i} scope="row">
        <td>{candidate}</td>
        <td>1</td>
        <td>1</td>
        <td>1</td>
        {this.getAvailability(availabilities, i)}
      </tr>);
    });

    return tableData;
  }

  render() {

    return (
    <table className="table-bordered text-center">
      <thead>
        <tr>
        <th>Date</th><th scope="col">○</th><th scope="col">△</th><th scope="col">×</th>
        {this.userNameTableHeader()}
        </tr>
        {this.availabilitiesTableData()}
      </thead>
      <tbody>

      </tbody>
    </table>
    );
  }
}

class UserAddForm extends React.Component {

  constructor(props) {
    super(props);

    const candidates = document.getElementsByClassName('candidates');
   
    const usersNode = document.getElementById('users');
    const candidatesNode = document.getElementById('candidates');
    const availabilitiesNode = document.getElementById('availabilities');

    const usersObj = JSON.parse(usersNode.innerHTML);
    const candidatesObj = JSON.parse(candidatesNode.innerHTML);
    const availabilitiesObj = JSON.parse(availabilitiesNode.innerHTML);

    this.users = Object.values(usersObj);
    this.candidatesDate = Object.values(candidatesObj);
    this.availabilities = Object.values(availabilitiesObj);
   
   /////// //////
   
    // const node = document.getElementById('availabilities');

    // const obj = JSON.parse(node.innerHTML);
    
    // this.availabilities = Object.values(obj).map(values => {
    //   return values;
    // });

    // setTimeout( () => {
    //   node.parentNode.removeChild(node);
    // }, 1);

    // const users = document.getElementsByClassName('users');
    
    // Array.prototype.forEach.call(users, user => {
      
    //   user.addEventListener('click', () => {

    //       const userName = this.unescapeUserName(user.innerHTML);
    //       const userId = user.userId;
    //       submit_button.value = "Update user";

    //       const candidates = this.state.candidates;

    //       Array.prototype.map.call(candidates, (key, candidate) => {
    //         return candidate.value = this.availabilities[key][userId];
    //       });

    //       this.setState({
    //         candidates: candidate,
    //         ishidden: true,
    //         userName: userName,
    //         userId: userId,
    //       })
      
    //     });
    // });

    ///////////////

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
    this.onChangeUserName = this.onChangeUserName.bind(this);
  }

  unescapeUserName (str) {

    const patterns = {
            '&lt;': '<',
            '&gt;': '>',
            '&amp;': '&',
            '&quot;': '"',
            '&#x27;': '\'',
            '&#x60;': '`',
    }

    return str.replace(/&(lt|gt|amp|quot|#x27|#x60);/g, (match) => {
      return patterns[match];
    });

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

  onChangeUserName(e) {
    this.setState({
      userName: e.target.value
    });
  }


  render() {
    const candidates = this.state.candidates;
    return (
      <div>
        <AvailabilitiesTable users={this.users} candidates={this.candidatesDate} availabilities={this.availabilities}/>
        <UserName userName={this.state.userName} userId={this.state.userId} onChange={this.onChangeUserName}/>
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
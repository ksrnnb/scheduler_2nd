import React from 'react';
import ReactDOM from 'react-dom';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";

import { faAngleDoubleRight, faAngleDoubleLeft } from '@fortawesome/free-solid-svg-icons'



class Schedule extends React.Component {

  constructor(props) {
    super(props);
    const today = new Date();
    this.state = {
      list: [],
      today: {
        year: today.getFullYear(),
        month: today.getMonth(),
      }
    };
    this.handleSubmit = this.handleSubmit.bind(this);
    this.handleClick = this.handleClick.bind(this);
    this.arrowClick = this.arrowClick.bind(this);
  }

  handleSubmit(e) {

    // this.state.list: array
    if (this.state.list.length) {
      
    } else {
      alert('Need to select candidates from calender.')
      e.preventDefault();
    }
  }

  handleClick(e) {

    let list = this.state.list.slice();

    const td = e.target;
    const date = td.dataset.date;

    if (td.classList.contains('selected')) {
      list = list.filter(item => {
        return item != date;
      });
    } else {
      list.push(date);
      list.sort((a, b) => new Date(a) - new Date(b));
    }

    this.setState({
      list: list
    });
    
  }

  arrowClick(e) {

    // persist()がないとエラーが出る。
    // https://ja.reactjs.org/docs/events.html#event-pooling
    e.persist();

    let year = this.state.today.year;
    let month = this.state.today.month;

    // e.targetだと何も返ってこないときがあった。なんでかは分からん。

    if (e.currentTarget.id === 'left-arrow') {
      month--;
    } else if (e.currentTarget.id === 'right-arrow') {
      month++;
    } else {
      console.log('Element doesn\'t have ID!');
    }

    const today = new Date(year, month, 1);
    
    this.setState({
        today: {
          year: today.getFullYear(),
          month: today.getMonth(),
        }
    });
  }

  render() {

    return (
      <div>
        <ScheduleName />
        <Calender onClick={this.handleClick} arrowClick={this.arrowClick} today={this.state.today} list={this.state.list}/>
        <CandidatesList list={this.state.list}/>
        <MakeScheduleButton handleSubmit={this.handleSubmit}/>
      </div>
    );
  }
}


class ScheduleName extends React.Component {
  render() {
    return (
      <div>
        <p className="input-title">Schedule Name</p>
        <input type="text" className="w-100" name="scheduleName" required />
      </div>
    );
  }
}

class Calender extends React.Component {

  constructor(props) {
    super(props);
    this.handleClick = this.props.onClick.bind(this);
    this.arrowClick = this.props.arrowClick.bind(this);
  }

  isSelected(fullDate) {
    if (this.props.list.indexOf(fullDate) === -1) {
      return false;
    }
    return true;
  }

  render () {
    
    const isSelected = this.isSelected.bind(this);

    const weeks = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    const year = this.props.today.year;
    const month = this.props.today.month;

    const monthEndDay = new Date(year, month + 1, 0).getDate();

    const lastMonthEndDay = new Date(year, month, 0).getDate();
    const lastMonthEndWeek = new Date(year, month, 0).getDay();

    const weeksTableData = [];

    weeks.forEach(week => {
      weeksTableData.push(<td key={week} className={week}>{week}</td>);
    });

    const dateTableRowData = [];

    // block scope (date)
    {
      let date = 1;

      const yearMonth = year + '/' + (month + 1) + '/';
      
      for (let row = 0; row < 6; row++) {

        const tableData = [];

        for (let week = 0; week < 7; week++) {

          // keyにindexを使うのは良くないのでやめた
          // const key = row + '_' + week;

          if (row === 0) {

            if (week <= lastMonthEndWeek) {
              const date_ = lastMonthEndDay - lastMonthEndWeek + week;
              const fullDate = year + '/' + month + '/' + date_;

              tableData.push(<td key={fullDate} className={"disable " + weeks[week]}>{date_}</td>);
              continue;
            } else {
              const fullDate = yearMonth + date;
              let classSelected = isSelected(fullDate) ? ' selected ' : ' ';
              tableData.push(<td onClick={this.handleClick} className={'able' + classSelected + weeks[week]} data-date={fullDate} key={fullDate}>{date}</td>);
            }
          } else {
            if (date <= lastMonthEndDay) {
              const fullDate = yearMonth + date;
              let classSelected = isSelected(fullDate) ? ' selected ' : ' ';
              tableData.push(<td onClick={this.handleClick} className={'able' + classSelected + weeks[week]} data-date={fullDate} key={fullDate}>{date}</td>)
            } else {
              const date_temp = date - monthEndDay;
              const fullDate = year + '/' + month + '/' + date_temp;
              tableData.push(<td key={fullDate} className={"disable " + weeks[week]}>{date - monthEndDay}</td>)
            }
          }
          date++;
        }

        // yearMonth + row -> fullDateのキーと被るけど兄弟要素じゃないから大丈夫、なはず
        dateTableRowData.push(<tr key={yearMonth + row}>{tableData}</tr>);

      }
    }

    return (
      <div id="calender-container">
        <h2>
          <FontAwesomeIcon id="left-arrow" className="arrow" icon={faAngleDoubleLeft} onClick={this.arrowClick} />
          <span id="current-month">{' ' + year}/{(month + 1) + ' '}</span>
          <FontAwesomeIcon id="right-arrow" className="arrow" icon={faAngleDoubleRight} onClick={this.arrowClick} />
        </h2>
        <table>
          <thead>
            <tr>
              {weeksTableData}
            </tr>
          </thead>
          <tbody>
            {dateTableRowData}
          </tbody>
        </table>
      </div>
    );
  }
}

class CandidatesList extends React.Component {

  render () {
    const weeks = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    let candidates = this.props.list.map(candidate => {
      const d = new Date(candidate);
      const month = d.getMonth() + 1;
      const date = d.getDate();
      const day = d.getDay();
      return month + '/' + date + ' (' + weeks[day] + ')'; 
    });

    const list = candidates.join('\n');

    return (
      <div>
        <p className="input-title">Candidates List</p>
        <textarea name="candidates" className="w-100" readOnly value={list}/>
      </div>
    );
  }
}

class MakeScheduleButton extends React.Component {

  constructor(props) {
    super(props);
  }

  render () {

    return (
      <div>
        <input className="btn btn-outline-primary" type="submit" value="Create schedule" onClick={this.props.handleSubmit}/>
      </div>
    );
  }
}



if (document.getElementById('index')) {
  ReactDOM.render(
    <Schedule />,
    document.getElementById('index')
  );
}
var React = require('react');
var ReactDOM = require('react-dom');
var $ = require('jquery');

var InputForm = React.createClass({
    handleSubmit: function (e) {
        e.preventDefault();
        var text = this.refs.text.value.trim();
        if (!text) {
            return;
        }
        this.props.onItemSubmit({text: text});
        this.refs.text.value = '';
        return;
    },
    render: function () {
        return (
            <form className="inputForm form-inline" role="form" onSubmit={this.handleSubmit}>
                <div className="form-group">
                    <input className="form-control" type="text" placholder="Task..." ref="text"/>
                </div>{' '}
                <button className="btn btn-default" type="submit">Add</button>
            </form>
        );
    }
});

var ListItem = React.createClass({
    handleItemUpdate: function(item) {
        $.ajax({
            url: this.props.url,
            dataType: 'json',
            type: 'PUT',
            data: item,
            success: function (data) {
                this.setState({data: data});
            }.bind(this),
            error: function (xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    getInitialState: function() {
        return {value: this.props.status};
    },
    handleChange: function(e) {
        this.setState({value: !this.state.value});
        this.handleItemUpdate({id: this.props.id, status: !this.state.value});
    },
    render: function () {
        var value = this.state.value;
        return (
            <li className="listItem list-group-item">
                <div className="checkbox">
                    <input type="checkbox" checked={value} onChange={this.handleChange}/>
                </div>
                {this.props.children}
            </li>
        );
    }
});

var TodoList = React.createClass({
    render: function () {
        var itemNodes = this.props.data.map(function (item) {
            return (
                <ListItem key={item.id} status={item.status} id={item.id} url="/list">
                    {item.text}
                </ListItem>
            );
        });

        return (
            <ul className="todoList list-group">
                {itemNodes}
            </ul>
        );
    }
});

var TodoBox = React.createClass({
    loadTodosFromServer: function () {
        $.ajax({
            url: this.props.url,
            dataType: 'json',
            cache: false,
            success: function (data) {
                data.status = data.status == 1;
                this.setState({data: data});
            }.bind(this),
            error: function (xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    handleItemSubmit: function (item) {
        $.ajax({
            url: this.props.url,
            dataType: 'json',
            type: 'POST',
            data: item,
            success: function (data) {
                this.setState({data: data});
            }.bind(this),
            error: function (xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    getInitialState: function () {
        return {data: []};
    },
    componentDidMount: function () {
        this.loadTodosFromServer();
        setInterval(this.loadTodosFromServer, this.props.pollInterval);
    },
    render: function () {
        return (
            <div className="todoBox">
                <InputForm onItemSubmit={this.handleItemSubmit}/>
                <TodoList data={this.state.data}/>
            </div>
        );
    }
});

ReactDOM.render(
    <TodoBox url="/list" pollInterval={5000} />,
    document.getElementById('app')
);

import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import { BrowserRouter, Route, Switch } from 'react-router-dom'
import Header from './layout/header'
import StockList from './stock/stock-index'
import StockCreateUpdate from './stock/stock-create-edit'
import StockShow from './stock/stock-show'

class App extends Component {
    render() {
        return (
            <BrowserRouter basename="/reactspa">
                <div>
                    <Header />
                    <Switch>
                        <Route exact path='/stocks' component={StockList} />
                        <Route exact path='/stocks/create' component={StockCreateUpdate} />
                        <Route exact path='/stocks/:id/edit' component={StockCreateUpdate} />
                        <Route exact path='/stocks/:id' component={StockShow} />
                    </Switch>
                </div>
            </BrowserRouter>
        )
    }
}

ReactDOM.render(<App />, document.getElementById('app'))
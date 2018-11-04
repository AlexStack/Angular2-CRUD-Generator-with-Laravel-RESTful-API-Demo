import axios from 'axios'
import React, { Component } from 'react'

class StockAdd extends Component {
    constructor(props) {
        super(props)
        this.state = {
            stock_name: '',
            industry: '',
            market_cap: '',
            id: '',
            errors: []
        }
        this.handleFieldChange = this.handleFieldChange.bind(this)
        this.handleCreateStockAdd = this.handleCreateStockAdd.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
    }

    handleFieldChange(event) {
        this.setState({
            [event.target.name]: event.target.value
        })
    }

    handleCreateStockAdd(event) {
        event.preventDefault()

        const { history } = this.props

        const stock =
        {
            //id: this.state.id,
            stock_name: this.state.stock_name,
            market_cap: this.state.market_cap,
            industry: this.state.industry
        }

        const stockId = this.props.match.params.id
        axios.put(`/api/stocks/${stockId}`, stock)
            .then(response => {
                // redirect 
                history.push('/stocks/' + stockId)
            })
            .catch(error => {
                this.setState({
                    errors: error.response.data.errors
                })
            })
    }

    hasErrorFor(field) {
        return !!this.state.errors[field]
    }

    renderErrorFor(field) {
        if (this.hasErrorFor(field)) {
            return (
                <span className='invalid-feedback'>
                    <strong>{this.state.errors[field][0]}</strong>
                </span>
            )
        }
    }

    componentDidMount() {
        const stockId = this.props.match.params.id
        this.setState({
            id: this.props.match.params.id
        })
        axios.get(`/api/stocks/${stockId}`).then(response => {
            let item = response.data.data;
            this.setState({
                stock_name: item.stock_name,
                market_cap: item.market_cap,
                industry: item.industry
            })
        })
    }

    render() {
        return (
            <div className='container py-4'>
                <div className='row justify-content-center'>
                    <div className='col-md-6'>
                        <div className='card'>
                            <div className='card-header'>Create new stock</div>
                            <div className='card-body'>
                                <form onSubmit={this.handleCreateStockAdd}>

                                    <div className={`form-group ${this.state.id != '' ? "d-none" : "show"}`}>
                                        <label htmlFor='id'>ID</label>
                                        <input
                                            id='id'
                                            type='number'
                                            className={`form-control ${this.hasErrorFor('id') ? 'is-invalid' : ''}`}
                                            name='id'
                                            value={this.state.id}
                                            onChange={this.handleFieldChange}
                                        />
                                        {this.renderErrorFor('id')}
                                    </div>


                                    <div className='form-group'>
                                        <label htmlFor='stock_name'>Project name</label>
                                        <input
                                            id='stock_name'
                                            type='text'
                                            className={`form-control ${this.hasErrorFor('stock_name') ? 'is-invalid' : ''}`}
                                            name='stock_name'
                                            value={this.state.stock_name}
                                            onChange={this.handleFieldChange}
                                        />
                                        {this.renderErrorFor('stock_name')}
                                    </div>
                                    <div className='form-group'>
                                        <label htmlFor='industry'>Project industry</label>
                                        <textarea
                                            id='industry'
                                            className={`form-control ${this.hasErrorFor('industry') ? 'is-invalid' : ''}`}
                                            name='industry'
                                            rows='10'
                                            value={this.state.industry}
                                            onChange={this.handleFieldChange}
                                        />
                                        {this.renderErrorFor('industry')}
                                    </div>

                                    <div className='form-group'>
                                        <label htmlFor='market_cap'>Market Cap</label>
                                        <input
                                            id='market_cap'
                                            type='text'
                                            className={`form-control ${this.hasErrorFor('market_cap') ? 'is-invalid' : ''}`}
                                            name='market_cap'
                                            value={this.state.market_cap}
                                            onChange={this.handleFieldChange}
                                        />
                                        {this.renderErrorFor('market_cap')}
                                    </div>

                                    <button className={`btn ${this.state.stock_name == '' ? 'disabled btn-secondary' : 'btn-primary'}`}>Edit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

export default StockAdd
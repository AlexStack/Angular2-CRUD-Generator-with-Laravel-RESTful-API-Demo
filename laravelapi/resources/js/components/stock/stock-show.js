import axios from 'axios'
import React, { Component } from 'react'
import { Link } from 'react-router-dom'

class StockShow extends Component {
    constructor(props) {
        super(props)
        this.route_basic = 'stocks';
        this.api_basic = '/api/stocks';        
        this.state = {
            stock: {},
            tasks: []
        }
    }

    componentDidMount() {
        const stockId = this.props.match.params.id

        axios.get(`${this.api_basic}/${stockId}`).then(response => {
            this.setState({
                stock: response.data.data
            })
        })
    }

    render() {
        const { stock, tasks } = this.state

        return (
            <div className='container py-4'>
                <div className='row justify-content-center'>
                    <div className='col-md-8'>
                        <div className='card'>
                            <div className='card-header'>{stock.stock_name}</div>
                            <div className='card-body'>
                                <p>{stock.industry}</p>

                                <p>{stock.currency_code} {stock.market_cap}</p>
                                <hr />

                                {stock.sector}

                                -

                                {stock.updated_at}

                                - <Link
                                    className='edit'
                                    to={`/stocks/${stock.id}/edit`}
                                >
                                    <span>Edit</span>
                                </Link>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

export default StockShow
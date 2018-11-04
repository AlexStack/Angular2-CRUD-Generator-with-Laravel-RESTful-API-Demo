import axios from 'axios'
import React, { Component } from 'react'
import { Link } from 'react-router-dom'

class StockList extends Component {
    constructor() {
        super()
        this.state = {
            stocks: []
        }
    }

    componentDidMount() {
        axios.get('/api/stocks', {
            params: {
                limit: 35,
                orderBy: 'id',
                sortedBy: 'desc'
            }
        })
            .then(response => {
                let rs = response.data;
                this.setState({ stocks: rs.data });

            });
    }

    render() {
        const { stocks } = this.state
        return (
            <div className='container py-4'>
                <div className='row justify-content-center'>
                    <div className='col-md-8'>
                        <div className='card'>
                            <div className='card-header'>All stocks</div>
                            <div className='card-body'>
                                <Link className='btn btn-primary btn-sm mb-3' to='/stocks/create'>
                                    Create new stock
                    </Link>
                                <ul className='list-group list-group-flush'>
                                    {stocks.map(stock => (
                                        <li className='list-group-item list-group-item-action d-flex justify-content-between align-items-center'
                                            key={`list_${stock.id}`} >
                                            <Link
                                                className='show'
                                                to={`/stocks/${stock.id}`}
                                                key={`show_${stock.id}`}
                                            >
                                                {stock.stock_name}
                                                <span className='badge badge-primary badge-pill ml-2'>
                                                    {stock.market_cap}
                                                </span>
                                            </Link>
                                            <Link
                                                className='edit'
                                                to={`/stocks/${stock.id}/edit`}
                                                key={`edit_${stock.id}`}
                                            >
                                                <span>Edit</span>
                                            </Link>
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

export default StockList
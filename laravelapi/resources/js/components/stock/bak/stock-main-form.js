import React, { Component } from "react";

class CreateEditMainForm extends Component {
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
                                            type='number'
                                            className={`form-control ${this.hasErrorFor('market_cap') ? 'is-invalid' : ''}`}
                                            name='market_cap'
                                            value={this.state.market_cap}
                                            onChange={this.handleFieldChange}
                                        />
                                        {this.renderErrorFor('market_cap')}
                                    </div>

                                    <button className='btn btn-primary'>Create</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}
export default CreateEditMainForm
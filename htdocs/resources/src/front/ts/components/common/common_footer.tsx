import * as React from 'react'
import { connect } from 'react-redux'

class CommonFooter extends React.Component {
  constructor(props) {
    super(props)
  }

  render(): JSX.Element {
    return (
      <React.Fragment>
        <footer className="footer">
          <div className="footer_inner">
            <p className="mt20">
              <img src="/assets/front/image/logo_02.png" alt="" />
            </p>
            <p className="mt10">
              <small className="fz-s">
                ©️isystk このページは架空のページです。実際の人物・団体とは関係ありません。
              </small>
            </p>
          </div>
        </footer>
      </React.Fragment>
    )
  }
}

const mapDispatchToProps = {}

export default connect(null, mapDispatchToProps)(CommonFooter)

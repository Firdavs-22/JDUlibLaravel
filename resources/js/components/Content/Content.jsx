import {NavLink} from "react-router-dom";

const Content = ({children, contentName, breadcrumb}) => {
    return (
        <div className="content-wrapper">
            <section className="content-header">
                <div className="container-fluid">
                    <div className="row mb-2">
                        <div className="col-sm-6">
                            <h1>{contentName}</h1>
                        </div>
                        <div className="col-sm-6">
                            <ol className="breadcrumb float-sm-right">
                                {breadcrumb.map((item, index) => (
                                    <li className={`breadcrumb-item ${index === breadcrumb.length - 1 ? 'active' : ''}`}
                                        key={index}>
                                        {item.url ? <NavLink to={item.url}>{item.title}</NavLink> : item.title}
                                    </li>
                                ))}
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section className="content">
                <div className="container-fluid">
                    {children}
                </div>
            </section>
        </div>
    );
}

export default Content;

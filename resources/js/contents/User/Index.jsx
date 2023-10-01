import {useEffect} from "react";

const Index = ({setPageTitle, setBreadcrumbs}) => {
    const pageTitle = 'Index';

    useEffect(() => {
        setPageTitle(pageTitle);
    },[]);

    useEffect(() => {
        setBreadcrumbs([
            {title: 'User', url: '/user'},
        ]);
    },[])

    return (
        <h1>User page</h1>
    )
}

export default Index

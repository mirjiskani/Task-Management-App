export function Table({ columns, data }) {
    return (
        <table style={{ width: "100%", borderCollapse: "collapse", background: "#fff", boxShadow: "0 4px 24px rgba(0,0,0,0.08)", borderRadius: 8 }}>        
            <thead>
                <tr>
                    {columns.map((col, i) => (  
                        <th key={i} style={{ textAlign: "left", padding: "12px 16px", borderBottom: "2px solid #eee", background: "#f9f9f9", color: "#555", fontWeight: 600, fontSize: 14 }}>
                            {col.header}
                        </th>       
                    ))}
                </tr>
            </thead>    
            <tbody>
                {data.map((row, i) => (
                    <tr key={i} style={{ borderBottom: "1px solid #eee" }}>
                        {columns.map((col, j) => (
                            <td key={j} style={{ padding: "12px 16px", color: "#333", fontSize: 14 }}>
                                {col.accessor === "actions" ? (
                                    <>
                                        <button style={{ marginRight: 8, padding: "4px 12px", background: "#1976d2", color: "#fff", border: "none", borderRadius: 4, cursor: "pointer" }}>Edit</button>
                                        <button style={{ padding: "4px 12px", background: "#e53935", color: "#fff", border: "none", borderRadius: 4, cursor: "pointer" }}>Delete</button>
                                    </>
                                ) : (
                                    row[col.accessor]
                                )}
                            </td>
                        ))}
                    </tr>
                ))}
            </tbody>
        </table>
    )};


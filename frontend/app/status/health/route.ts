export async function GET(){
    try {
        return new Response(JSON.stringify({
            status: "ok",
            uptime: process.uptime(),
            timeStamp: new Date().toISOString()
        }), {
            status: 200,
        headers: {
                "Content-Type": "application/json"
            }
        });
    }catch (error) {
        return new Response(JSON.stringify({
            status: "error",
            message: "An error occurred while checking the health of the application.",
            error: error instanceof Error ? error.message : "Unknown error"
        }), {
            status: 503,
            headers: {
                "Content-Type": "application/json"
            }
        });
    }
}
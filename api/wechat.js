export default function handler(req, res) {
    try {
        if (req.method === "POST") {
            const { id, timestamp, message } = req.body;

            if (!id || !timestamp || !message) {
                return res.status(400).json({ error: "Missing required fields" });
            }

            console.log("Received POST:");
            console.log("ID:", id);
            console.log("Timestamp:", timestamp);
            console.log("Message:", message);

            return res.status(200).json({ success: true, id, timestamp });
        } else {
            return res.status(405).json({ error: "Method not allowed" });
        }
    } catch (error) {
        console.error("Error in handler:", error);
        return res.status(500).json({ error: "Internal Server Error" });
    }
}
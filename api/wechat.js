export default function handler(req, res) {
    try {
        if (req.method === "POST") {
            const { message } = req.body;

            if (!message) {
                return res.status(400).json({ error: "Message is required" });
            }

            console.log("Received message:", message);
            return res.status(200).json({ success: true, message: "Message received" });
        } else {
            return res.status(405).json({ error: "Method not allowed" });
        }
    } catch (error) {
        console.error("Error in handler:", error);
        return res.status(500).json({ error: "Internal Server Error" });
    }
}